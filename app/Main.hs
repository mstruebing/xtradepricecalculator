module Main where

import System.Environment

data Item = Iron | Water | Food | Steel | Electronics

iron_default_price :: Int
iron_default_price = 1
water_default_price :: Int
water_default_price = 2
food_default_price :: Int
food_default_price = 3
steel_default_price :: Int
steel_default_price = 4
eleectronics_default_price :: Int
eleectronics_default_price = 5

main = do 
    args <- getArgs
    let iron = dropWhile (/= "--iron") args
    let water = dropWhile (/= "--water") args
    let food = dropWhile (/= "--food") args
    let steel= dropWhile (/= "--steel") args
    let electronics = dropWhile untilNextArgument $ args
    let ironAmount = takeWhile untilNextArgument $ tail iron
    let waterAmount = takeWhile untilNextArgument $ tail water
    let foodAmount = takeWhile untilNextArgument $ tail food
    let steelAmount = takeWhile untilNextArgument $ tail steel
    let electronicsAmount = takeWhile untilNextArgument $ tail electronics

    mapM putStrLn args
    putStrLn "EHLO"
    mapM putStrLn iron
    putStrLn "EHLO"
    mapM putStrLn ironAmount

    where
        untilNextArgument x = head x /= '-'
